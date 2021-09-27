#!/bin/bash

# Create .env 
sed \
    -e "s;%COMPOSE_PROJECT_NAME%;$COMPOSE_PROJECT_NAME;g" \
    -e "s;%SERVERNAME%;$SERVERNAME;g" \
    -e "s;%HTTP_PORT%;$HTTP_PORT;g" \
    -e "s;%PLUGIN_ACF_KEY%;$PLUGIN_ACF_KEY;g" \
    ./.env.template > .env

# Run after deploy to install depnencies and start server
./bin/after-deploy.sh