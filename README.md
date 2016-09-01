# Chess DB + Jenkins research

## Project
    this is a chessdb
    Also I tried to do some jenkins research for a talk

## Getting started

in your shell:

```bash
# start dev box (this only starts the dev vm)
vagrant up dev

# go into dev box
vagrant ssh dev

# in box, cd to project
cd /srv/share/chessdb/symfony

# initialize project
make init
```


## Start the jenkins box
```bash
# start box
vagrant up jenkins

# ssh into box
vagrant ssh jenkins

# in box, switch user to jenkins ;)
sudo su - jenkins
```

