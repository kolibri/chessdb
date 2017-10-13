ANSIBLE_USER=kalel
ANSIBLE_HOST=

help:             ## shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

deploy: 	      ## run provisioning on the given host. Needs ANSIBLE_HOST to be set!
ifndef ANSIBLE_HOST
$(error ANSIBLE_HOST is not set)
endif

	ansible-playbook -u $(ANSIBLE_USER) -i "$(ANSIBLE_HOST)," deploy.yml
