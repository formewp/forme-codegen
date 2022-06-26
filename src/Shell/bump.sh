#!/bin/bash

#Get the highest tag number
CURRENT_VERSION=`git describe --abbrev=0 --tags`
# remove the v from the front
CURRENT_VERSION=${CURRENT_VERSION#v}
# set it to 0.0.0 if it's empty
CURRENT_VERSION=${CURRENT_VERSION:-'0.0.0'}

#Get number parts
MAJOR="${CURRENT_VERSION%%.*}"; CURRENT_VERSION="${CURRENT_VERSION#*.}"
MINOR="${CURRENT_VERSION%%.*}"; CURRENT_VERSION="${CURRENT_VERSION#*.}"
PATCH="${CURRENT_VERSION%%.*}"; CURRENT_VERSION="${CURRENT_VERSION#*.}"

#Increase version number depending on the scope argument, default to patch
case $BUMP_SCOPE in
  major) MAJOR=$((MAJOR+1)); MINOR=0; PATCH=0;;
  minor) MINOR=$((MINOR+1)); PATCH=0;;
  patch) PATCH=$((PATCH+1)); ;;
  *) PATCH=$((PATCH+1)); ;;
esac

#Get current hash and see if it already has a tag
GIT_COMMIT=`git rev-parse HEAD`
HAS_TAG=`git describe --contains $GIT_COMMIT 2>/dev/null`

#Create new tag
NEW_VERSION="$MAJOR.$MINOR.$PATCH"

# Only tag if no tag already
if [ -z "$HAS_TAG" ]; then
    # if style.css exists, this is a theme
    if [ -f style.css ]; then
        echo "Updating theme version to $NEW_VERSION"
        # find the line with "Version:" and replace it with the new version
        sed -i '' -e "s/Version:.*/Version: $NEW_VERSION/g" style.css
    else
        echo "Updating plugin version to $NEW_VERSION"
        # find the file that contains '* Version: ' and store it in FILENAME variable
        FILENAME=`grep -l -d skip "* Version: " *`
        # find the line with "* Version:" and replace it with the new version
        sed -i '' -e "s/\* Version:.*/\* Version: $NEW_VERSION/g" $FILENAME
    fi
    # if gir cliff is installed Generate a changelog
    if command -v git-cliff &> /dev/null
    then
        echo "Generating changelog with git cliff"
        git cliff -o CHANGELOG.md -t $NEW_VERSION
        git add CHANGELOG.md
    else
        echo "We couldn't find git cliff so skipping changelog. You can install it with 'brew install git-cliff' or 'cargo install git-cliff'"
        echo "Then run 'git cliff --init' in the project directory"
    fi
    echo "Committing new version changes and pushing"
    git commit -a -m "chore: bump version"
    git push
    echo "Tagging with v$NEW_VERSION and pushing"
    git tag "v$NEW_VERSION"
	git push --tags
else
    echo "There is already a tag on the current commit - abandoning bump"
    exit 1
fi
