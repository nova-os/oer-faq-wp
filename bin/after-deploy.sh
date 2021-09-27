#!/bin/bash
# Install dependencies
./bin/composer install
# Build web container
docker-compose build
# Recreate Web container
docker-compose up --force-recreate -d web
