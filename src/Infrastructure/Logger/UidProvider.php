<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Logger;

class UidProvider
{
    private const SIZE = 40;

    /** @var string */
    private $path;

    /** @var string */
    private $uid;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        if ($this->uid) {
            return $this->uid;
        }

        // 戻り値でエラーをハンドリングするのでE_WARNINGの発生を抑制
        $fp = @fopen($this->path, 'c+');
        if ($fp === false) {
            throw new \RuntimeException();
        }

        try {
            if (flock($fp, LOCK_EX) === false) {
                throw new \RuntimeException();
            }

            try {
                return $this->uid = $this->doGet($fp);
            } finally {
                if (flock($fp, LOCK_UN) === false) {
                    throw new \RuntimeException();
                }
            }
        } finally {
            if (fclose($fp) === false) {
                throw new \RuntimeException();
            }
        }
    }

    /**
     * @param resource $fp
     * @return string
     */
    private function doGet($fp): string
    {
        $uid = fread($fp, self::SIZE);
        if (strlen($uid) === self::SIZE) {
            return $uid;
        }

        $uid = sha1(microtime() . (string)mt_rand());

        $writeSize = fwrite($fp, $uid);
        if ($writeSize !== self::SIZE) {
            throw new \RuntimeException();
        }

        if (fflush($fp) === false) {
            throw new \RuntimeException();
        }

        return $uid;
    }
}