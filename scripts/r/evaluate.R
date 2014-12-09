library("randomForest")
library("CORElearn")

source("razlaga.R")

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

# exampleToEvaluate is without rating and weight
exampleToEvaluate = read.csv(parsedCsvFile, na.strings=c("", "NA", "NULL"), header=TRUE, colClasses=colClassesUsed)
colClassesUsed["weight"] = "NULL"
colClassesUsed["rating"] = "NULL"
trainingSet = read.csv("../../data/dataset_extended.csv", na.strings=c("", "NA", "NULL"), header=TRUE, colClasses=colClassesUsed)
selectedAttributes = readRDS("../../data/rds/selectedAttributes.rds")

rfRegular = readRDS("../../data/rds/rf.rds")
rfNormalized = readRDS("../../data/rds/rfNormalized.rds")

trainingSetNormalized = trainingSet
exampleToEvaluateNormalized = exampleToEvaluate

# normalize and discretize everything
attributeNames = names(trainingSetNormalized)
for (i in 1:length(attributeNames)) {
    # rating is already normalized
    if (attributeNames[i] != 'rating' && attributeNames[i] != 'weight') {
        if (is.factor(trainingSetNormalized[, attributeNames[i]]) != TRUE) {
            if (is.logical(trainingSet[, attributeNames[i]])) {
                trainingSetNormalized[, attributeNames[i]] = trainingSetNormalized[, attributeNames[i]] * 1
                trainingSetNormalized[, attributeNames[i]] = factor(trainingSetNormalized[, attributeNames[i]])
                exampleToEvaluateNormalized[, attributeNames[i]] = factor(exampleToEvaluateNormalized[, attributeNames[i]] * 1, levels = c("0", "1"))
            } else {
                maxVal = max(trainingSetNormalized[, attributeNames[i]], exampleToEvaluateNormalized[1, attributeNames[i]])
                trainingSetNormalized[, attributeNames[i]] = as.numeric(trainingSetNormalized[, attributeNames[i]] / maxVal)
                exampleToEvaluateNormalized[, attributeNames[i]] = as.numeric(exampleToEvaluateNormalized[, attributeNames[i]] / maxVal)
            }
        }
    }
}

# instance explanation
instanceExplanation = explainInstance(rfNormalized, trainingSetNormalized, exampleToEvaluateNormalized[1,], 200)
instanceAttributes = names(instanceExplanation$instance[1:length(instanceExplanation$instance)-1])
instData = data.frame(attribute = factor(instanceAttributes), explanation = instanceExplanation$explanation)

attrToRemove = c()
for (i in 1:length(instData$attributes)) {
    if (!instData$attributes[i] %in% selectedAttributes) {
        attrToRemove = c(attrToRemove, i)
    }
}
instData = instData[-attrToRemove,]

# rating prediction
rating = predict(rfRegular, exampleToEvaluate[1, ])

url = exampleToEvaluate$url
filename = basename(parsedCsvFile)

ratingData = data.frame(url = url, rating)

# write data
write.csv(instData, file = paste("../../exports/explained/", filename, sep = ""), row.names = FALSE)
write.csv(ratingData, file = paste("../../exports/evaluated/", filename, sep = ""), row.names = FALSE)
