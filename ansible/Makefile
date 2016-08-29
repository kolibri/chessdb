deploy:
	ansible-playbook deploy.yml -e project_sub_path="" --limit=live

deploy-branch:
	ansible-playbook deploy.yml -e project_sub_path=$(BRANCH) --limit=stage

# @todo: finish this
tarball:
	cd ../symfony && tar -czf ../chessdb.tar.gz .