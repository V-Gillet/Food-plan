FROM node:20


ENV NODE_EXTRA_CA_CERTS=/etc/ssl/certs/ca-certificates.crt

# Mise à jour de npm
RUN npm i --global npm

WORKDIR /app

ADD fixuid.tar.gz /usr/local/bin
RUN chown root:root /usr/local/bin/fixuid && chmod 4755 /usr/local/bin/fixuid && mkdir -p /etc/fixuid
COPY fixuid.yml /etc/fixuid/config.yml
USER node

RUN yarn config set cafile ${NODE_EXTRA_CA_CERTS} --global
RUN npm config set prefix /home/node/.npm-packages
ENV PATH="${PATH}:/home/node/.npm-packages/bin"

VOLUME /app
VOLUME /home/node/.cache
ENTRYPOINT ["fixuid", "-q", "docker-entrypoint.sh"]
CMD ["node"]
