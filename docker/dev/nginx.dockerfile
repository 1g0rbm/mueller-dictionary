FROM nginx:1.21-alpine

RUN apk add --no-cache curl

COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /app
