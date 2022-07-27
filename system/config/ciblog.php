<?php

$_['ciblog_config_layouts'] = '[
  {
    "name": "Ci All blogs",
    "route": "extension/ciblog/ciblog"
  },
  {
    "name": "Ci Blog Category Page",
    "route": "extension/ciblog/cicategory"
  },
  {
    "name": "Ci Blog Search",
    "route": "extension/ciblog/cisearch"
  },
  {
    "name": "Ciblog Blog Page",
    "route": "extension/ciblog/ciblogpost"
  },
  {
    "name": "Ciblog Authors Page",
    "route": "extension/ciblog/ciauthor"
  },
  {
    "name": "Ciblog Author info Page",
    "route": "extension/ciblog/ciauthor/info"
  },
  {
    "name": "Ciblog subscriber verify page",
    "route": "extension/ciblog/cisubscriber/verify"
  }
]
';




$_['ciblog_store_defaults'] =  '{
  "ciblog_store_status": "1",
  "ciblog_store_can_like": "BOTH",
  "ciblog_store_rating_show": "1",
  "ciblog_store_post_count": "0",
  "ciblog_store_page_keyword": "",
  "ciblog_store_page_description": {
    "1": {
      "blog_title": "",
      "description": "",
      "meta_title": "",
      "meta_description": "",
      "meta_keyword": ""
    }
  },
  "ciblog_store_blog_limit": "10",
  "ciblog_store_blog_row": "2",
  "ciblog_store_blog_description_length": "200",
  "ciblog_store_blog_show_title": "1",
  "ciblog_store_blog_show_description": "1",
  "ciblog_store_blog_image_listing_width": "438",
  "ciblog_store_blog_image_listing_height": "292",
  "ciblog_store_blog_image_show_listing": "1",
  "ciblog_store_blog_show_date_publish": "1",
  "ciblog_store_blog_show_total_view": "1",
  "ciblog_store_blog_show_author": "1",
  "ciblog_store_blog_like_show_total": "1",
  "ciblog_store_blog_comment_show_total": "1",
  "ciblog_store_blog_image_thumb_width": "1024",
  "ciblog_store_blog_image_thumb_height": "683",
  "ciblog_store_blog_image_popup_width": "1024",
  "ciblog_store_blog_image_popup_height": "683",
  "ciblog_store_blog_image_additional_width": "424",
  "ciblog_store_blog_image_additional_height": "283",
  "ciblog_store_blog_image_show_thumb": "1",
  "ciblog_store_blogpage_show_date_publish": "1",
  "ciblog_store_blogpage_show_total_view": "1",
  "ciblog_store_blogpage_show_author": "1",
  "ciblog_store_blogpage_like_show_total": "1",
  "ciblog_store_blogpage_like_allow": "1",
  "ciblog_store_blogpage_show_social_share": "1",
  "ciblog_store_blogpage_comment_allow": "1",
  "ciblog_store_blogpage_comment_allow_guest": "1",
  "ciblog_store_blogpage_comment_show_total": "1",
  "ciblog_store_blogpage_comment_show": "1",
  "ciblog_store_blogpage_comment_snippet": "0",
  "ciblog_store_blogpage_comment_limit": "10",
  "ciblog_store_blogpage_comment_captcha": "1",
  "ciblog_store_blogpage_comment_approve": "BOTH",
  "ciblog_store_blogpage_comment_alert": "1",
  "ciblog_store_blogrelated_row": "2",
  "ciblog_store_blogrelated_description_length": "200",
  "ciblog_store_blogrelated_show_title": "1",
  "ciblog_store_blogrelated_show_description": "1",
  "ciblog_store_blogrelated_image_listing_width": "438",
  "ciblog_store_blogrelated_image_listing_height": "292",
  "ciblog_store_blogrelated_image_show_listing": "1",
  "ciblog_store_blogrelated_show_date_publish": "1",
  "ciblog_store_blogrelated_show_total_view": "1",
  "ciblog_store_blogrelated_show_author": "1",
  "ciblog_store_blogrelated_like_show_total": "1",
  "ciblog_store_blogrelated_comment_show_total": "1",
  "ciblog_store_blogcategory_limit": "10",
  "ciblog_store_blogcategory_row": "2",
  "ciblog_store_blogcategory_description_length": "200",
  "ciblog_store_blogcategory_show_title": "1",
  "ciblog_store_blogcategory_show_description": "1",
  "ciblog_store_blogcategory_image_listing_width": "438",
  "ciblog_store_blogcategory_image_listing_height": "292",
  "ciblog_store_blogcategory_image_show_listing": "1",
  "ciblog_store_blogcategory_show_date_publish": "1",
  "ciblog_store_blogcategory_show_total_view": "1",
  "ciblog_store_blogcategory_show_author": "1",
  "ciblog_store_blogcategory_like_show_total": "1",
  "ciblog_store_blogcategory_comment_show_total": "1",
  "ciblog_store_blogsearch_limit": "10",
  "ciblog_store_blogsearch_row": "2",
  "ciblog_store_blogsearch_description_length": "200",
  "ciblog_store_blogsearch_show_title": "1",
  "ciblog_store_blogsearch_show_description": "1",
  "ciblog_store_blogsearch_image_listing_width": "438",
  "ciblog_store_blogsearch_image_listing_height": "292",
  "ciblog_store_blogsearch_image_show_listing": "1",
  "ciblog_store_blogsearch_show_date_publish": "1",
  "ciblog_store_blogsearch_show_total_view": "1",
  "ciblog_store_blogsearch_show_author": "1",
  "ciblog_store_blogsearch_like_show_total": "1",
  "ciblog_store_blogsearch_comment_show_total": "1",
  "ciblog_store_blogauthor_limit": "10",
  "ciblog_store_blogauthor_row": "2",
  "ciblog_store_blogauthor_description_length": "200",
  "ciblog_store_blogauthor_show_title": "1",
  "ciblog_store_blogauthor_show_description": "1",
  "ciblog_store_blogauthor_image_listing_width": "438",
  "ciblog_store_blogauthor_image_listing_height": "292",
  "ciblog_store_blogauthor_image_show_listing": "1",
  "ciblog_store_blogauthor_show_date_publish": "1",
  "ciblog_store_blogauthor_show_total_view": "1",
  "ciblog_store_blogauthor_show_author": "1",
  "ciblog_store_blogauthor_like_show_total": "1",
  "ciblog_store_blogauthor_comment_show_total": "1"
}';