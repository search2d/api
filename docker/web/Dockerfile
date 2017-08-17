FROM nginx:1.13-alpine

COPY docker/web/nginx.conf /etc/nginx/nginx.conf.template
CMD envsubst < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf && nginx -g 'daemon off;'