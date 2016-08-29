REPORT_DIR = 'reports'
ENV=dev

## Build targets

build: composer-install npm-install gulp-default

init: build database-create import-local-fixtures

composer-install:
	composer install

npm-install:
	npm install

gulp-default:
	./node_modules/.bin/gulp default

database-create:
	./bin/console doctrine:database:create --env=$(ENV)
	./bin/console doctrine:schema:create --env=$(ENV)

database-drop:
	./bin/console doctrine:database:drop --force --env=$(ENV)

database-rebuild: database-drop database-create --env=$(ENV)

import-local-fixtures:
	./bin/console pgn:import:files app/Resources/fixtures/pgns --env=$(ENV)

tarball:
	tar -czf ../chessdb.tar.gz .

## Local QA targets

phpunit:
	./vendor/bin/phpunit

lint: lint-php lint-twig lint-yaml
lint-yaml:
	./bin/console lint:yaml app/config
	./bin/console lint:yaml src

lint-twig:
	./bin/console lint:twig app/Resources/views

lint-php:
	find ./src -name "*.php" -print0 | xargs -0 -n1 -P8 php -l
	find ./tests -name "*.php" -print0 | xargs -0 -n1 -P8 php -l


## CI targets

report-dir:
	mkdir -p $(REPORT_DIR)

phpunit-report: report-dir
	./vendor/bin/phpunit --coverage-html=$(REPORT_DIR)/phpunit-html-coverage --log-junit=$(REPORT_DIR)/phpunit.junit.xml --coverage-clover=$(REPORT_DIR)/phpunit.clover.xml

phpcs-report: report-dir
	phpcs --report=checkstyle --report-file=$(REPORT_DIR)/phpcs.cs.xml --standard=PSR1,PSR2 src/ tests/

phpmd-report: report-dir
	phpmd src/ xml cleancode,codesize,controversial,design,naming,unusedcode --reportfile $(REPORT_DIR)/phpmd.pmd.xml

phpcpd-report: report-dir
	phpcpd --log-pmd=$(REPORT_DIR)/phpcpd.dry.xml src/

insight-report: insight-analyze insight-analises

insight-analyze:
	insight analyze 8b4bc69b-0874-468a-b1f2-bc828543d907

insight-analises:
	insight analysis 8b4bc69b-0874-468a-b1f2-bc828543d907 --format=pmd > $(REPORT_DIR)/insight.pmd.xml

security-report:
	security-checker security:check
#deptrac-report:
#	deptrac 


## other targets

import-dropbox:
	./bin/console pgn:import:dropbox
