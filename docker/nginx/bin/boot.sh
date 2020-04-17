#!/usr/bin/env bash

export DEFAULT_DOMAIN=${HOST:-_}

vars='${DEFAULT_DOMAIN}'

envsubst "$vars" < /etc/nginx/conf.d/api.tmpl > /etc/nginx/conf.d/api.conf

nginx -g "daemon off;"
