# Tik Cms Api

## About

This package only used for Tik CMS. This cms devloped by tikweb. So, if you use it, you can use 
tik-cms-api


## Features

* Handles related API for Tik CMS


## Installation

Require the `devghor/tik-cms-api` package in your `composer.json` and update your dependencies:
```sh
composer require devghor/tik-cms-api
```

## API

| Method | Endpoint                    | Description                       |
|:--------------------|:----------------------------|:----------------------------------|
| GET | `/tikcms/page/get/all/language/published/design?page_name={your_page_name}`   | Get Specific Page's Designs For All Language. |
| GET | `/tikcms/page/show/published?page_name={your_page_name}&page_language={your_page_language}`   | Get Specific Page's Design |
| GET | `/tikcms/blog/all/show`   | Get the collection of blogs |
| GET | `/tikcms/blog/show/published?blog_id={your_blog_id}`   |Pass a blog ID and in return get that specific published blog |
| GET | `/tikcms/blog/all/published/show`   | Get the collection of published blogs |

