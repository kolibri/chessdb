REPORT_DIR=reports
ENV=dev
COMPOSER_CMD=composer
NPM_CMD=npm
GULP_CMD=gulp
PHPUNIT_CMD=phpunit
SYMFONY_CMD=php bin/console
SECURITY_CHECKER_CMD=security-checker
PHPCS_CMD=phpcs
PHPCBF_CMD=phpcbf
PHPMD_CMD=phpmd
PHPCPD_CMD=phpcpd
APP_FIXTURES=dev

# build targets
build: composer-install npm-install gulp-build
dev-init: build reset-database
prod-init: build reset-database
composer-install:
	$(COMPOSER_CMD) install
npm-install:
	$(NPM_CMD) install
gulp-build:
	$(GULP_CMD) build
reset-database:
	-$(SYMFONY_CMD) doctrine:database:drop --force
	$(SYMFONY_CMD) doctrine:database:create
	$(SYMFONY_CMD) doctrine:schema:create
	$(SYMFONY_CMD) app:fixtures $(APP_FIXTURES)
tarball:
	tar -czf ../chessdb.tar.gz . --exclude ./reports --exclude ./node_modules --exclude ./var

# test targets
test: lint phpunit #security-checker
lint: lint-php lint-twig lint-yaml
lint-yaml:
	$(SYMFONY_CMD) lint:yaml app
	$(SYMFONY_CMD) lint:yaml src
lint-twig:
	$(SYMFONY_CMD) lint:twig app/Resources/views
lint-php:
	find ./src -name "*.php" -print0 | xargs -0 -n1 -P8 php -l
	find ./tests -name "*.php" -print0 | xargs -0 -n1 -P8 php -l
phpunit:
	$(PHPUNIT_CMD) $(OPTIONS)
security-checker:
	$(SECURITY_CHECKER_CMD) security:check composer.lock

# Cody analysis targets
qa: phpcs phpmd phpcpd
phpcs:
	$(PHPCS_CMD) --standard=phpcs.xml $(OPTIONS)
phpcbf:
	-$(PHPCBF_CMD) --standard=phpcs.xml
phpmd:
	$(PHPMD_CMD) src xml phpmd.xml $(OPTIONS)
phpcpd:
	$(PHPCPD_CMD) $(OPTIONS) src/

## report targets
reports: lint phpunit-report security-checker phpcs-report phpcpd-report

phpunit-report: report-dir
	$(MAKE) OPTIONS='--coverage-html=$(REPORT_DIR)/phpunit-html-coverage --log-junit=$(REPORT_DIR)/phpunit.junit.xml --coverage-clover=$(REPORT_DIR)/phpunit.clover.xml' phpunit

phpcs-report: report-dir
	$(MAKE) OPTIONS='--report=checkstyle --report-file=$(REPORT_DIR)/phpcs.cs.xml' phpcs

phpmd-report: report-dir
	$(MAKE) OPTIONS='--reportfile $(REPORT_DIR)/phpmd.pmd.xml' phpmd

phpcpd-report: report-dir
	$(MAKE) OPTIONS='--log-pmd=$(REPORT_DIR)/phpcpd.dry.xml' phpcpd

report-dir:
	mkdir -p $(REPORT_DIR)
