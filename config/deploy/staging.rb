set :stage, :production
set :branch, "master"
set :rsync_location, "vagrant"

# used in case we're deploying multiple versions of the same
# app side by side. Also provides quick sanity checks when looking
# at filepaths
set :full_app_name, "#{fetch(:application)}_#{fetch(:stage)}"

# send to vagrant
server 'deploy@127.0.0.1:2222', user: 'deploy', roles: %w{web app db}, primary: true

set :deploy_to, "/home/#{fetch(:deploy_user)}/apps/#{fetch(:full_app_name)}"

### CHANGE THIS TO BE FOR PHP
# dont try and infer something as important as environment from
# stage name.
set :rails_env, :production

# whether we're using ssl or not, used for building nginx
# config file
set :enable_ssl, false
