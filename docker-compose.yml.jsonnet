local ddb = import 'ddb.docker.libjsonnet';

local domain_ext = std.extVar('core.domain.ext');
local domain_sub = std.extVar('core.domain.sub');

local domain = std.join('.', [domain_sub, domain_ext]);

ddb.Compose({
  services: {
    db: ddb.Build('mysql') + ddb.User() +
        {
          environment+: {
          MYSQL_ROOT_PASSWORD: 'ddb',
          MYSQL_DATABASE: 'food-plan'
          },
          volumes+: [
            'db-data:/var/lib/mysql',
            ddb.path.project + ':/project',
          ],
        },
    php: ddb.Build('php') +
         ddb.Binary('php', '/var/www/html', 'php') +
         ddb.Binary('composer', '/var/www/html', 'composer') +
         ddb.Binary('symfony', '/var/www/html', 'symfony') +
         ddb.User() +
         {
           volumes+: [
             ddb.path.project + ':/var/www/html',
             'php-composer-cache:/composer/cache',
             'php-composer-vendor:/composer/vendor',
           ],
         },
    web: ddb.Build('web') +
         ddb.VirtualHost('80', domain)
         {
           volumes+: [
             ddb.path.project + ':/var/www/html',
             ddb.path.project + '/.docker/web/apache.conf:/usr/local/apache2/conf/custom/apache.conf',
           ],
         },
    node: ddb.Build('node') +
          ddb.User() +
          ddb.Binary('node', '/app', 'node') +
          ddb.Binary('npm', '/app', 'npm') +
          ddb.Binary('npx', '/app', 'npx') +
          {
            volumes+: [
              ddb.path.project + ':/app',
              'node-cache:/home/node/.cache',
            ],
            tty: true,
          },
  },
})
