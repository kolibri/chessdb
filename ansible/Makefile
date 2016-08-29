deploy:
	ansible-playbook deploy.yml -e project_sub_path=""

deploy-branch:
	ansible-playbook deploy.yml -e project_sub_path=$(BRANCH)

# @todo: finish this
tarball:
	cd ../symfony && tar -czf ../chessdb.tar.gz .