# Changelog
All notable changes to this project will be documented in this file.

## [2.7.3] - 2026-02-08

### Bug Fixes

- Php fpm in nginx

## [2.7.2] - 2026-02-08

### Miscellaneous Tasks

- Update docker file for ketch
- Bump version

## [2.7.1] - 2026-02-08

### Bug Fixes

- Correct tmp path

### Miscellaneous Tasks

- Bump version

## [2.7.0] - 2026-02-08

### Features

- Specify port for test server

### Miscellaneous Tasks

- Bump version

## [2.6.1] - 2026-02-08

### Bug Fixes

- Start server in ipv4

### Documentation

- Comment the last change for clarity

### Miscellaneous Tasks

- Bump version

## [2.6.0] - 2026-02-07

### Miscellaneous Tasks

- Update ketch php to 8.2
- Update docker compose to latest syntax
- Bump version

## [2.5.0] - 2026-02-07

### Miscellaneous Tasks

- Drop 8.1 support and upgrade deps
- Bump version

## [2.4.2] - 2025-06-22

### Miscellaneous Tasks

- Bump deps
- Bump version

## [2.4.1] - 2025-02-21

### Miscellaneous Tasks

- Accept symfony console 6
- Bump version

## [2.4.0] - 2025-02-21

### Miscellaneous Tasks

- Upgrade php parser
- Update pest
- Bump version

## [2.3.4] - 2025-02-20

### Miscellaneous Tasks

- Update deps
- Update more deps
- Update nette
- Bump version

## [2.3.3] - 2024-09-25

### Bug Fixes

- Symfony console v6 does not like type defs on defaultname

### Miscellaneous Tasks

- Bump version

## [2.3.2] - 2024-09-11

### Miscellaneous Tasks

- Integrate tempdir adapter as lib is abandoned
- Bump version

## [2.3.1] - 2024-09-11

### Miscellaneous Tasks

- Update deps
- Update forme framework dep
- Bump version

## [2.3.0] - 2024-09-03

### Features

- Make wrangle command

### Miscellaneous Tasks

- Update deps
- Update deps
- Bump version

## [2.2.4] - 2024-06-11

### Miscellaneous Tasks

- Remove the lerna example from help
- Update deps
- Bump version

## [2.2.3] - 2024-06-04

### Bug Fixes

- Another version spoof for test setup

### Miscellaneous Tasks

- Bump version

## [2.2.2] - 2024-06-04

### Bug Fixes

- Spoof 8.0 for later versions of wordpress

### Miscellaneous Tasks

- Bump version

## [2.2.1] - 2024-06-04

### Bug Fixes

- Command name annotations

### Miscellaneous Tasks

- Bump version

## [2.2.0] - 2024-06-04

### Miscellaneous Tasks

- Upgrade pest and other deps
- Bump version

## [2.1.4] - 2024-06-04

### Miscellaneous Tasks

- Update deps
- Bump version

## [2.1.3] - 2024-02-08

### Miscellaneous Tasks

- Update composer
- Bump version

## [2.1.2] - 2023-05-15

### Miscellaneous Tasks

- Update php deps
- Bump version

## [2.1.1] - 2023-05-11

### Bug Fixes

- Casing fix - pascal not camel

### Miscellaneous Tasks

- Move some deps from phive to composer dev
- Bump version

### Testing

- Minor import namespace fix

## [2.1.0] - 2023-04-25

### Miscellaneous Tasks

- Update forme framework
- Bump version
- Update forme
- Bump version

## [2.0.1] - 2023-04-25

### Miscellaneous Tasks

- Add vscode settings and recommendations
- Fix phpstan setting
- Update deps
- Bump version

## [2.0.0] - 2023-03-11

### Miscellaneous Tasks

- Merge branch 'master'
- Bump dependencies
- Bump version

## [1.10.2] - 2023-03-01

### Bug Fixes

- Subst syntax

### Miscellaneous Tasks

- Update deps
- Bump version

## [1.10.1] - 2023-03-01

### Miscellaneous Tasks

- Update deps and ketch/docker
- Don't add Service to service class names
- Bump version

## [1.10.0] - 2023-01-15

### Bug Fixes

- Errors should error
- Remove hardcoded test symlink
- Bash equals syntax
- Reverse bash error

### Documentation

- Test command help text

### Features

- Test commands

### Miscellaneous Tasks

- Add the test setup stubs
- Redundant tmp dir rm
- Bump version

### Refactor

- A bit of sugar and refactor

## [1.9.2] - 2022-10-01

### Miscellaneous Tasks

- Update framework dep
- Bump version

## [1.9.1] - 2022-10-01

### Documentation

- Update readme

### Miscellaneous Tasks

- Update composer deps
- Bump version

## [1.9.0] - 2022-09-12

### Miscellaneous Tasks

- Bump version

### Refactor

- Rename translators to transformers

## [1.8.7] - 2022-09-03

### Bug Fixes

- Hook builder method check

### Dev Workflow

- Fix bump script

### Features

- Hook method default to __invoke

### Miscellaneous Tasks

- Bump version

## [1.8.6] - 2022-07-30

### Bug Fixes

- Composer location remove line ending

### Miscellaneous Tasks

- Bump version

## [1.8.5] - 2022-07-28

### Miscellaneous Tasks

- Use global ecs if global forme install
- Bump version

## [1.8.4] - 2022-07-27

### Miscellaneous Tasks

- Update controllers sig to match abstract
- Bump version

## [1.8.3] - 2022-07-19

### Miscellaneous Tasks

- Better args handling
- Bump version

### Refactor

- Add a constant for the app dir

### Testing

- Test migration builder

## [1.8.2] - 2022-07-09

### Features

- Array formatting field group

### Miscellaneous Tasks

- Bump version

### Refactor

- Push the formatting to builder scope

## [1.8.1] - 2022-07-07

### Documentation

- Update help and spec with field enum feat

### Miscellaneous Tasks

- Bump version

## [1.8.0] - 2022-07-07

### Features

- Scaffold out field enum feature
- Field enum command flow
- Field group parsing poc
- Ask user for field enum names
- Field enum class creation
- Field enum formatting and class config
- Add the static self enum methods
- Acf enum mvp
- Use alias for new enum

### Miscellaneous Tasks

- Explicitly require php parser
- Merge branch master
- Bump version

### Refactor

- Use temp file system instead of ad hoc file
- Extract out resolver logic
- Extracts relevant data in visitor
- Extract resolver into two methods
- Extract field enum questions to separate class

### Testing

- Field enum builder test
- Refactor class builder test to use stub
- Remove empty tests
- Enum field group builder test

## [1.7.25] - 2022-07-03

### Dev Workflow

- Update git cliff config
- Update php stan config

### Documentation

- Update changelog

### Miscellaneous Tasks

- Move docker compose stub
- Bump version

### Refactor

- Yaml formatting fixer static

### Testing

- Add test for hook builder

## [1.7.24] - 2022-07-03

### Dev Workflow

- Update conventional commit config

### Miscellaneous Tasks

- Update composer deps
- Bump version

## [1.7.23] - 2022-07-02

### Dev Workflow

- Add ramsey conventional

### Miscellaneous Tasks

- Local php cs fixer
- Bump version

## [1.7.22] - 2022-07-02

### Bug Fixes

- Explicitly call composer hooks after initial commit

### Miscellaneous Tasks

- Bump version

## [1.7.21] - 2022-07-02

### Miscellaneous Tasks

- Update new command amount
- Bump version

## [1.7.20] - 2022-07-02

### Miscellaneous Tasks

- Bump version

## [1.7.19] - 2022-06-28

### Documentation

- Update readme

### Miscellaneous Tasks

- Add infection to composer scripts and phive
- Update deps
- Merge branch 'master'
- Replace deprecated method call
- Bump version

### Refactor

- Extract new shell script replacer logic
- Magic strings into constants
- Delete unused func
- Remove redundant func

### Testing

- Add infection and add generator factory test
- Update infection to use pest
- New shell script replacer
- Show mutations when running infection
- Name space resolver
- Refactor resolver test
- Remove broken phive phpstan

## [1.7.18] - 2022-06-26

### Bug Fixes

- Phar build without icon

### Miscellaneous Tasks

- Bump version

## [1.7.17] - 2022-06-26

### Miscellaneous Tasks

- Bump version

### Refactor

- Temp dir not needed for bump

## [1.7.16] - 2022-06-26

### Miscellaneous Tasks

- Bump version

### Refactor

- New command use temp directory
- Bump use temp fs

## [1.7.15] - 2022-06-26

### Bug Fixes

- Php env

### Miscellaneous Tasks

- Bump version

### Styling

- Line before success output

## [1.7.14] - 2022-06-26

### Miscellaneous Tasks

- Tidy up new command output
- Bump version

## [1.7.13] - 2022-06-26

### Features

- Progress bar instead of output

### Miscellaneous Tasks

- Bump version

## [1.7.12] - 2022-06-26

### Miscellaneous Tasks

- Update composer deps
- Bump version

## [1.7.11] - 2022-06-26

### Features

- Notifications for bigger commands

### Miscellaneous Tasks

- Bump version

## [1.7.10] - 2022-06-26

### Miscellaneous Tasks

- Test script pest instead of phpunit
- Bump version

## [1.7.9] - 2022-06-26

### Documentation

- Update readme

### Miscellaneous Tasks

- Replace phpunit with pest
- Bump version

## [1.7.8] - 2022-06-26

### Miscellaneous Tasks

- Suppress irrelevant bump error
- Bump version

## [1.7.7] - 2022-06-26

### Miscellaneous Tasks

- Suppress bump error
- Bump version

## [1.7.6] - 2022-06-26

### Bug Fixes

- Bump amend forme php

### Features

- Bump takes argument

### Miscellaneous Tasks

- Bump version

## [1.7.5] - 2022-06-26

### Miscellaneous Tasks

- Move forme to php so it lints
- Bump version

## [1.7.4] - 2022-06-26

### Bug Fixes

- Bump syntax

### Miscellaneous Tasks

- Bump version

## [1.7.3] - 2022-06-26

### Features

- Add changelog composer script
- Bump version

### Miscellaneous Tasks

- Make bump executable
- Bump version

### Refactor

- Replace githooks with captainhook

## [1.7.2] - 2022-06-26

### Documentation

- Changelog

### Miscellaneous Tasks

- Move vendor/bin to tools
- Update version

## [1.7.1] - 2022-06-26

### Miscellaneous Tasks

- Wrap functions and type
- Move some dev tools to phive

### Testing

- Fix tests

## [1.7.0] - 2022-06-25

### Documentation

- Changelog

### Features

- Phar build poc

### Miscellaneous Tasks

- Add features to roadmap
- Fix typo
- Update version

## [1.6.4] - 2022-06-17

### Bug Fixes

- Property syntax for phptoken

### Miscellaneous Tasks

- Bump version

## [1.6.3] - 2022-05-07

### Miscellaneous Tasks

- Update deps
- Bump version

## [1.6.2] - 2022-05-06

### Documentation

- Changelog

### Miscellaneous Tasks

- Update deps

## [1.6.1] - 2022-04-28

### Miscellaneous Tasks

- Update controllers source
- Bump version

## [1.6.0] - 2022-04-28

### Miscellaneous Tasks

- Update forme framework and bump version

## [1.5.5] - 2022-04-28

### Miscellaneous Tasks

- Update composer deps and bump version

## [1.5.4] - 2022-04-19

### Bug Fixes

- Typing issue in getClass resolver

### Documentation

- Update changelog

### Miscellaneous Tasks

- Bump version

## [1.5.3] - 2022-04-19

### Documentation

- Update changelog
- Update changelog

### Miscellaneous Tasks

- Update composer deps
- Bump version

## [1.5.2] - 2022-04-18

### Miscellaneous Tasks

- Bump version number

### Refactor

- More rector rules

## [1.5.1] - 2022-04-18

### Miscellaneous Tasks

- Add rector to our dev toolkit
- Update version number

### Refactor

- Rector php 8 updates

## [1.5.0] - 2022-03-19

### Bug Fixes

- Null coalesce

### Miscellaneous Tasks

- Update deps
- Update phpstan config

## [1.4.1] - 2022-02-04

### Features

- Support twig and plates 3

### Miscellaneous Tasks

- Bump version

## [1.4.0] - 2022-02-01

### Documentation

- Update changelog

### Features

- View engine extract
- Configure view engine

### Miscellaneous Tasks

- Bump version

## [1.3.12] - 2022-01-31

### Bug Fixes

- More codegen file system issues

### Documentation

- Changelog

### Miscellaneous Tasks

- Version bump

## [1.3.11] - 2022-01-31

### Bug Fixes

- Use correct filesystem instance

## [1.3.10] - 2022-01-31

### Bug Fixes

- Help directory

### Documentation

- Update sed further
- Update spec
- Add packages json with autocomplete instructions
- Add manual steps for docs
- Changelog

### Miscellaneous Tasks

- Bump version
- Fix bump version

## [1.3.9] - 2022-01-29

### Documentation

- Update markdown output bash
- Update sed for doc generation
- Tweak the sed command further
- Localhost link

## [1.3.8] - 2022-01-28

### Documentation

- How to export help as md
- Update spec description
- Add expanded ketch help
- Fix typos
- Add expanded make help
- Expand new help
- Add emoji to each help md
- Update readme and titles

### Features

- Add cli markdown and poc base help in md doc
- Add expanded bump help

### Refactor

- Abstract command

## [1.3.7] - 2022-01-02

### Miscellaneous Tasks

- Set tty for all pass through commands
- Bump patch version

## [1.3.6] - 2022-01-01

### Miscellaneous Tasks

- Update deps

## [1.3.5] - 2022-01-01

### Features

- Autocomplete for make and new
- Bump completion and validation
- Complete for base and ketch

### Miscellaneous Tasks

- Bump version patch

## [1.3.4] - 2022-01-01

### Miscellaneous Tasks

- Disable timeout for longer commands
- Bump patch version

## [1.3.3] - 2022-01-01

### Features

- List alias ps plus document restart

### Miscellaneous Tasks

- Bump patch version

## [1.3.2] - 2022-01-01

### Documentation

- Imrpove the make command help

### Miscellaneous Tasks

- Bump patch version

## [1.3.1] - 2022-01-01

### Documentation

- Update spec doc re base

### Features

- Allow hyphens in base project dir name

### Miscellaneous Tasks

- Bump patch version

## [1.3.0] - 2022-01-01

### Features

- Base command

### Miscellaneous Tasks

- Bump minor version

## [1.2.3] - 2022-01-01

### Bug Fixes

- Db host set

## [1.2.2] - 2021-12-31

### Bug Fixes

- Project name and wp set

## [1.2.1] - 2021-12-31

### Features

- Also update wp config

### Miscellaneous Tasks

- Bump patch version

## [1.2] - 2021-12-31

### Documentation

- Update spec document
- Add ketch commands to spec doc
- Some usage info
- Better help text

### Features

- Ketch command first pass
- Check for docker file before pass through
- First pass at ketch link command
- Add container shell access

### Miscellaneous Tasks

- Add property type
- Bump minor version

### Refactor

- Split out methods

## [1.1.0] - 2021-12-27

### Features

- Bump version command

### Miscellaneous Tasks

- Remove version from composer

## [1.0.1] - 2021-12-25

### Miscellaneous Tasks

- Remove php 7.3
- Bump version
- Bump version in command

## [1.0] - 2021-12-24

### Documentation

- Add logo to readme

### Features

- Initial commit

### Miscellaneous Tasks

- Add license

### Testing

- Fix class builder test

<!-- generated by git-cliff -->
