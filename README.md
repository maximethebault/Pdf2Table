[![Build Status](https://travis-ci.org/maximethebault/Pdf2Table.svg?branch=master)](https://travis-ci.org/maximethebault/Pdf2Table)

# Pdf2Table

Parser for PDF files containing tables.

## Prerequisites

You'll first need to install PdfMiner, which doesn't work with Python3.
A simple "pip install pdfminer" should do it!

## Installation

This library uses composer. Just add the "maximethebault/Pdf2Table" package into the require section of your composer.json file.

## Usage

Have a look at the tests. Usage example is the fastest way to learn how to use the library!

## Limitations / Disclaimer

This library was developped for a specific project, and wasn't roughly tested for edgy cases.
Therefore, it may not manage multiple tables on a single page, table shared between several pages, ...
If you need any of these features, feel free to open a new issue!