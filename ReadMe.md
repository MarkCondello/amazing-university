## Setup
To build assets using the latest version of Node, the following script needs to be included in the build:
```
"start": "cross-env NODE_OPTIONS=--openssl-legacy-provider wp-scripts start"
```

## Workflow

## Saving / loading ACF updates
the compose package (sage-advanced-custom-fields)[https://github.com/MWDelaney/sage-advanced-custom-fields] is used in mu-plugins to store changes to the fields as code changes