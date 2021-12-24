FROM node:15.12.0
WORKDIR /usr/src/effective-succotash
COPY ./package.json .
RUN npm install --only=prod