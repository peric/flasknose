library("randomForest")

colClassesUsed = c(
    "url" = "factor",
    "text" = "integer",
    "html_elements" = "integer",
    "headings" = "integer",
    "paragraphs" = "integer",
    "images" = "integer",
    "font_families" = "integer",
    "font_sizes" = "integer",
    "links" = "integer",
    "divs" = "integer",
    "ids" = "integer",
    "classes" = "integer",
    "css_external" = "integer",
    "css_internal" = "integer",
    "css_inline" = "integer",
    "css_declaration_blocks" = "NULL",
    "css_prefixes" = "logical",
    "js_sources" = "integer",
    "meta_tags" = "integer",
    "has_meta_keywords" = "logical",
    "has_meta_description" = "logical",
    "rss" = "logical",
    "import" = "logical",
    "twitter_bootstrap" = "logical",
    "html5_tags" = "integer",
    "html5" = "logical",
    "css_transitions" = "logical",
    "flash" = "logical",
    "page_weight" = "numeric",
    "media_queries" = "logical",
    "conditional_comments" = "logical",
    "included_multimedia" = "logical",
    "minified_css" = "logical",
    "font_families_list" = "NULL",
    "h1_font" = "NULL",
    "h2_font" = "NULL",
    "h3_font" = "NULL",
    "h4_font" = "NULL",
    "h5_font" = "NULL",
    "p_font" = "NULL",
    "a_font" = "NULL",
    "reset_css" = "logical",
    "normalize_css" = "logical",
    "css_pseudo_elements" = "logical",
    "no_js" = "logical",
    "html_errors" = "integer",
    "colors" = "integer",
    "color_palette" = "NULL",
    "dominant_color" = "NULL"
)

args = commandArgs(TRUE)

parsedCsvFile = args[1]

# csv file is without rating and without weight
exampleToEvaluate = read.csv(parsedCsvFile, na.strings=c("", "NA", "NULL"), header=TRUE, colClasses=colClassesUsed)

# model
rf <- readRDS("../../data/rf.rds")

rating = predict(rf, exampleToEvaluate[1, ])

url = exampleToEvaluate$url
filename = basename(parsedCsvFile)

result = data.frame(url = url, rating)

write.csv(result, file = paste("../../exports/evaluated/", filename, sep = ""), row.names = FALSE)
