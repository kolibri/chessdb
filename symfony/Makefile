REPORT_DIR = 'reports'
ENV=dev

# build targets
build: composer-install npm-install gulp-build

composer-install:
	composer install
npm-install:
	npm install
gulp-build:
	gulp build

reset-database:
	./bin/console doctrine:database:drop --force
	./bin/console doctrine:database:create
	./bin/console doctrine:schema:update --force
	./bin/console app:fixtures

tarball:
	tar -czf ../chessdb.tar.gz . --exclude ./reports --exclude ./node_modules --exclude ./var

# test targets
test-report: lint phpunit-report
test: lint phpunit 
lint: lint-php lint-twig lint-yaml

lint-yaml:
	./bin/console lint:yaml app/config
	./bin/console lint:yaml src

lint-twig:
	./bin/console lint:twig app/Resources/views

lint-php:
	find ./src -name "*.php" -print0 | xargs -0 -n1 -P8 php -l
	find ./tests -name "*.php" -print0 | xargs -0 -n1 -P8 php -l

phpunit:
	phpunit
phpunit-report: report-dir
	phpunit --coverage-html=$(REPORT_DIR)/phpunit-html-coverage --log-junit=$(REPORT_DIR)/phpunit.junit.xml --coverage-clover=$(REPORT_DIR)/phpunit.clover.xml

# Cody analysis targets
qa-report: phpcs-report phpcpd-report
qa: phpcs phpmd phpcpd

phpcs:
	phpcs --standard=phpcs.xml
phpcbf:
	phpcbf --standard=phpcs.xml
phpcs-report: report-dir
	phpcs --standard=phpcs.xml --report=checkstyle --report-file=$(REPORT_DIR)/phpcs.cs.xml

phpmd:
	phpmd src xml phpmd.xml
phpmd-report: report-dir
	phpmd src xml phpmd.xml --reportfile $(REPORT_DIR)/phpmd.pmd.xml

phpcpd:
	phpcpd src/
phpcpd-report: report-dir
	phpcpd --log-pmd=$(REPORT_DIR)/phpcpd.dry.xml src/

report-dir:
	mkdir -p $(REPORT_DIR)
