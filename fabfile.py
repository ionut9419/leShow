from __future__ import with_statement
from fabric.api import run, cd, env
from fabric.contrib.console import prompt, confirm
from fabric.utils import puts
import time
import getpass

env.hosts = ['178.62.9.114']
env.user = prompt('Server Username? (leave blank for current user)')
if len(env.user) is 0:
    env.user = getpass.getuser()

env.staging_home_dir = "/home/staging"
env.staging_dir = "%(staging_home_dir)s/final-runner" % env

env.repo_url = "git@github.com:daniel-stanciulescu/final-runner.git"
env.release = time.strftime('%Y%m%d%H%M%S')

env.docker_cli = "docker-compose -f production.yml run --rm symfony"
env.symfony_cli = "%(docker_cli)s php app/console" % env

env.db_name = "finalrunner"
env.db_host = "localhost"
env.db_user = "staging"
env.db_backup_path = "%(staging_home_dir)s/db_backups" % env
env.current_db_backup_file = "%(db_backup_path)s/%(release)s.gz" % env

env.files_backup_path = "%(staging_home_dir)s/files_backups" % env
env.current_backup_archive = "%(files_backup_path)s/%(release)s.tar.gz" % env


def stage_pull():
    run("git pull")


def resolve_dependencies():
    run("%(docker_cli)s composer install" % env)


def clear_cache():
    run("%(symfony_cli)s cache:clear" % env)


def backup_db():
    puts("Backup db")

    env.db_password = getpass.getpass(
        "Enter your database password for %(db_user)s:" % env
    )

    run("mysqldump --user=%(db_user)s --password=%(db_password)s \
        --default-character-set=utf8 %(db_name)s | \
        gzip > %(current_db_backup_file)s" % env)

    run("ln -s %(current_db_backup_file)s %(db_backup_path)s/live.gz" % env)

    puts("Backup script completed")
    puts(
        "Your backups has been created in %(db_backup_path)s directory"
        % env
    )


def update_db():
    run("%(symfony_cli)s doctrine:schema:update --force" % env)


def backup_files():
    run("tar czvf %(current_backup_archive)s %(staging_dir)s/web" % env)
    run(
        "ln -s %(current_backup_archive)s %(files_backup_path)s/live.tar.gz"
        % env
    )


def stage():
    with cd("%(staging_dir)s" % env):
        stage_pull()
        if confirm("Resolve dependencies?"):
            resolve_dependencies()
        if confirm("Clear symfony cache?"):
            clear_cache()
        if confirm("Backup database?"):
            backup_db()
            if confirm("Update database schema?"):
                update_db()
        if confirm("Backup files?"):
            backup_files()


def rollback():
    run(
        "tar xzvf %(files_backup_path)s/live.tar.gz -C %(staging_dir)s/web"
        % env
    )

    env.db_password = getpass.getpass(
        "Enter your database password for %(db_user)s:" % env
    )

    run("zcat %(db_backup_path)s/live.gz | mysql --user=%(db_user)s \
        --password=%(db_password)s %(db_name)s" % env)
