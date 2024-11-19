FROM node:18-alpine AS builder

RUN mkdir /app

COPY . /app

RUN cd /app && yarn install && yarn build

WORKDIR /app

EXPOSE 3000

CMD ["node", "build/index.js"]