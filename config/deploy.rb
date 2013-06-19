set   :application,   "SimProduction"
set   :deploy_to,     "/home/simproduction/simproduction.es/"
set   :domain,        "simproduction.es"
set   :app_path,      "app"
set   :web_path,      "web"

ssh_options[:username] = 'simproduction'
ssh_options[:forward_agent] = true

role  :web,           domain
role  :app,           domain
role  :db,            domain, :primary => true

set   :use_sudo,      false
set   :keep_releases, 3

set   :scm,           :git
set   :repository,    "https://github.com/davidlallana/simproduction.git"
set   :user, "simproduction"
set   :deploy_via,    :remote_cache
set   :model_manager, "doctrine"

set   :php_bin,       "/usr/bin/php"
set   :branch,        "master"
set   :update_vendors,true