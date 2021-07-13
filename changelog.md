## Changelog

### 1.2.0
* Added options for Library (All / Uploaded to post) as requested in https://github.com/ooksanen/acf-focuspoint/issues/9 and Allowed file types

### 1.1.8
* Bugfix (1.1.7 related)

### 1.1.7
* Fixed a bug where you couldn't select/upload image if previously selected image was deleted

### 1.1.6
* Fixed a bug where multiple FocusPoint images in a repeater saved same top/left values for all images

### 1.1.5
* Fixed saving coordinates when no image selected (https://github.com/ooksanen/acf-focuspoint/issues/8).
* Use minified versions of input.js and input.css

### 1.1.4
* Fixed Required field validation returning false even on non-required field (https://github.com/ooksanen/acf-focuspoint/issues/7).

### 1.1.3
* Fixed Required field validation (https://github.com/ooksanen/acf-focuspoint/issues/6). Note that validation doesn't work in Gutenberg at the moment. See https://github.com/AdvancedCustomFields/acf/issues/113 for more info.

### 1.1.2
* Fixed image select form visibility on initial load and other minor (mostly) CSS issues.

### 1.1.1
* Bug fix: https://github.com/ooksanen/acf-focuspoint/issues/5

### 1.1.0
* Almost complete rewrite of javascript to add proper support for Gutenberg blocks.

### 1.0.4
* Added simple(Classic Editor only?) validation for min/max width, height, and file size

### 1.0.3
* Fixed a bug that prevented deleting focuspoint image
* Started implementing min/max width, height, and file size for image file

### 1.0.2
* Moved focus point selection to new element on top of the image to allow for more fine grained selection even very close to current selection

### 1.0.1
* Remove ACF 4 files / support which was never implemented
* Fixed bug where values weren't saved when using field in ACF Block
* Fixed selector for image hover cursor
* Minor CSS tweaks

### 1.0
* Initial release
