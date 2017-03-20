# Chess DB

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8b4bc69b-0874-468a-b1f2-bc828543d907/mini.png)](https://insight.sensiolabs.com/projects/8b4bc69b-0874-468a-b1f2-bc828543d907)

[![Build Status](https://travis-ci.org/kolibri/chessdb.svg?branch=master)](https://travis-ci.org/kolibri/chessdb)

## Project
    this is a chessdb in symfony

## Getting started

in your shell:

```bash
vagrant up

# go into dev box
vagrant ssh

# in box, cd to project
cd /srv/share

# initialize project
make dev-init
```

[Open the project in your browser](http://192.168.31.95/)


## Install on raspbian

# Prepare SD card

Download image from (https://www.raspberrypi.org/downloads/raspbian/) and run `./prepare_raspbian.sh`. Follow instructions.

# Provision

## Place inventory file

```
# ansible/inventory/pi
[pi]
pi ansible_ssh_host=<IP to rbpi>
```

## Initial run

```
$ ansible-playbook \
    --inventory inventory/pi \
    --limit pi \
    --user pi \
    site.yml \
    --ask-pass \ # this and follogin only on first run! Then never again.
    --extra-var="pi_github_access_token: placeTokenHere"
    --extra-var="pi_authorized_key_file: /path/to/home/.ssh/id_rsa.pub"
```

## Shortcut

`ansible-playbook -i inventory/pi -l pi -u pi site.yml`

## Post provision

```
ssh pi@<IP to rbpi>
cd /var/www/chessdb/
make build
./bin/console doctrine:database:create
./bin/console doctrine:schema:create
./bin/console app:fixtures prod_init
```
