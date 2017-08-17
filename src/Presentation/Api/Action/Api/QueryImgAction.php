<?php
declare(strict_types=1);

namespace Search2d\Presentation\Api\Action\Api;

use League\Tactician\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Search2d\Domain\Search\Image;
use Search2d\Domain\Search\ImageValidationException;
use Search2d\Presentation\Api\Helper;
use Search2d\Usecase\Search\QueryImgCommand;

class QueryImgAction
{
    /** @var \League\Tactician\CommandBus */
    private $commandBus;

    /** @var \Search2d\Presentation\Api\Helper */
    private $helper;

    /**
     * @param \League\Tactician\CommandBus $commandBus
     * @param \Search2d\Presentation\Api\Helper $helper
     */
    public function __construct(CommandBus $commandBus, Helper $helper)
    {
        $this->commandBus = $commandBus;
        $this->helper = $helper;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var \Psr\Http\Message\UploadedFileInterface[] $uploadedFiles */
        $uploadedFiles = $request->getUploadedFiles();
        if (!isset($uploadedFiles['img'])) {
            return $this->helper->responseFailure($response, 403, '画像のアップロードに失敗');
        }

        $uploadedFile = $uploadedFiles['img'];

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            return $this->helper->responseFailure($response, 403, '画像のアップロードに失敗');
        }

        try {
            $image = Image::create($uploadedFile->getStream()->getContents());
        } catch (ImageValidationException $e) {
            return $this->helper->responseFailure($response, 403, $e->getMessage());
        }

        /** @var \Search2d\Domain\Search\QueriedImage $queriedImage */
        $queriedImage = $this->commandBus->handle(new QueryImgCommand($image));

        $result = ['sha1' => $queriedImage->getSha1()->value];

        return $this->helper->responseSuccess($response, 201, $result);
    }
}