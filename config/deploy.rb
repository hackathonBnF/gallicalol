set :application, 'gallicalol'

set :repo_url, 'git@github.com:hackathonBnF/gallicalol.git'

set :deploy_to, '/var/www/gallicalol'

append :linked_files, '.env'

append :linked_dirs, 'db', 'cache', 'web/images'

SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

namespace :deploy do
  after :starting, 'composer:install_executable'
end

# Default value for keep_releases is 5
# set :keep_releases, 5
