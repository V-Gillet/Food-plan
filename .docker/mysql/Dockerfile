FROM mysql
ADD fixuid.tar.gz /usr/local/bin
RUN chown root:root /usr/local/bin/fixuid && chmod 4755 /usr/local/bin/fixuid && mkdir -p /etc/fixuid
COPY fixuid.yml /etc/fixuid/config.yml
USER mysql
ENTRYPOINT ["fixuid", "-q", "docker-entrypoint.sh"]
CMD ["mysqld"]
