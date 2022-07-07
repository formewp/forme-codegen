# Forme CodeGen

## Spec/Moscow

This is a working spec document that forms a product roadmap, and a reference for eventual documentation.

Specific requirements are bullet points and Moscow priority is shown by the verb used - Will/Should/Could/Won't

Ticks show if completed.

### Generated Class & File Types

Entity/Custom Post Type - `post-type`

- Will copy wp cli format and chuck it in a CustomPostType class ✅
- Should find and replace the placeholders in the cpt ✅
- Should also create an Eloquent model ✅

FieldGroup - `field`

- Will create a blank FieldGroup class ✅
- ~~Could create a Field Registry if there isn't one (no longer relevant)~~
- ~~Could add an entry to hooks.yaml for the registry if it's created one (no longer relevant)~~
- ~~Could add FieldGroup to the Field Registry(no longer relevant)~~
- ~~Won't create boilerplate ACF code programmatically (too complex/out of scope for now)~~

Field Enum - `field-enum`

- Will create a Field Enum class based on a field group class ✅
- Will update the field group class to use the enum ✅

Model - `model`

- Will create a blank Model class ✅
- Should set the post type using snake case of the name ✅

Controller - `controller`

- Will create a blank app Controller class ✅
- Should have option to create a blank template Controller class if theme
- Could optionally create a view and link it up

Shortcode - `shortcode`

- Will create a blank Shortcode class ✅

View

- Could create a blank view

Template - `template-controller`

- Could create a template controller ✅
- Could create an associated view

Hook - `action` `filter`

- Will add an entry to hooks.yaml for an existing class ✅
- Will request additional args via interactive cli ✅
- Could find methods from the selected class ✅
- Could default to method if there is only one
- Comments are preserved ✅

Registry - `registry`

- Will add a blank Registry class ✅
- Should add an entry to hooks.yaml from args using init ✅
- Could add an entry to hooks.yaml using choice of init or acf/init
- Could handle some standard types (Block, CustomPostType, ApiRoute)
- Could add an entry to hooks.yaml using the correct hook for the type

Service - `service`

- Will add a blank ad hoc service class ✅
- Will add named method to the class ✅
- Could add an optional entry to hooks.yaml

Translator - `translator`

- Will add a blank Translator class ✅

Migration - `migration`
- Should create a blank migration in the style of phinx ✅

Job - `job`
- Will create a blank job class ✅

Block

- Could add block boilerplate files - Block Registry, Block Renderer, and Block.js
- Could optionally hook up an existing controller into the Block Renderer
- Could optionally create a controller and inject it into the Block Renderer

Middleware
- Will create a blank Middleware class ✅

General

- All generated classes should have docblocks
- All generated classes should be final and strict ✅
- All generated classes should have typehinted methods
- Generator should not overwrite existing classes
- All internal classes should have docblocks
- All generated classes should have typehints ✅
- There will be simple user documentation
- There should be comprehensive user documentation


### Create New Projects

- Will clone theme or plugin boilerplate from github ✅
- Will run the gulp set up and clean up after itself ✅
- Will start a fresh local git repo ✅
- Should be able to handle non-default namespace ✅
- Should check for bash, git, node, npm, gulp
- Should work on Windows 10 ✅ (As long as correct environment, then yes)
- Should be able handle different github hosts ✅

### Create New Base Installations

- Will `composer create project` from `forme/base` ✅
- Will check for dependencies
- Will link plugin or theme directories into the base installation ✅
- Will run `forme/base` custom composer scripts ✅

### Docker

- Will initialise a basic docker environment similar to Laravel Sail ✅
- Will link plugin or theme directories into the docker environment ✅
- Will pass through npm, wp and composer commands into the docker environment ✅
- Will pass through docker compose commands ✅

### Bump Project Versions

- Will bump version number in plugin or theme files ✅
- Will update git tag ✅
- Will update changelog using git cliff ✅
- Will git commit and push all changes ✅

### Syntax and usage

- Initially this will at least work from within the codegen project folder ✅
- Library will work globally even though not installed globally ✅
- Library could be installed globally and `make` should work from within any relevant theme or plugin folder ✅
- `ketch` should only work from within any relevant base install ✅
- `base` should only work from within any relevant base install (except for `base new`) ✅
- `bump` should only work from within a relevant theme or plugin folder ✅

```bash
# Just install it globally
composer global require forme/codegen

# Or in case of any dependency issues use consolidation/cgr
cgr forme/codegen

# Or you can get it working globally from a local install
chmod +x forme
ln -s /path/to/installation/forme-codegen/forme /usr/local/bin/
```

- It should get the ProjectNameSpace automagically ✅
- It should validate and fail if `cwd` not a relevant theme or plugin folder ✅
- It should validate all inputs
- It is opinionated and follows the standard Forme framework file structure ✅

```bash
# Generates a class, view or hook
forme make <name> <type>
# Creates a new project
forme new <name> <type> <host(optional)>
# bump a project
forme bump <type(optional)>
# ketch for docker
forme ketch <command> <arguments>
# base installation utils
forme base <command> <arguments>
```

- The above syntax will work but without forme alias ✅
- The above syntax will work with forme alias ✅
- PHP Deprecation warnings are suppressed ✅
- Command outputs path to generated file ✅
- Command outputs detailed result on success ✅
- Command outputs useful error info on failure
- Make types should be validated ✅
- Messages should have emojis ✅
- Will have extended syntax or interactivity to include args for the other type requirements ✅
- Args could be requested interactively if not passed in - see [Question Helper](https://symfony.com/doc/current/components/console/helpers/questionhelper.html)

```bash
# Interactive Example
$ forme make
> What kind of file would you like to generate?
1) Controller
2) Registry
3) Custom Post Type
... etc
```

- Bash autocompletion should be functional for all commands ✅
- Eventually `make` could work from the base WP installation with additional arguments
= App directory should be configurable for all commands (e.g. `src` instead of `app`)
- Public directory should be configurable for all commands (e.g `public_html` instead of `public`)
