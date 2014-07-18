# Tree Testing
For a quick navigation concept testing, we needed a tool to ask the user questions like "Go and see if you can find Product XY". Presented with a simple tree navigation the user can now search for the solution to your question and "select" it - if he/she was successfull. If not he/she can always skip the question.

In the end a `.json` file is saved - or the whole data is sent to an email of your choice. If both things didnt work it will just output the result to the browser.

It tracks every click/open of navigation items and the selected item for the question.

There is no limit to the questions - and no limit to the different navigation variations as well. All questions will be asked for each navigation version.

## Variations
Simple take a look into the `vars` folder. Those files should be pretty self explaining. Create another version by adding `2.html` for example.

## Questions
Define as many questions as you like via the `questions.json` file. But beware: The last entry does not need a `,` at the end.

## Config
In the `config.php` file you can select which delivery method you'd like `mail` or `file`. If you choose `file` - give the folder write permissions, otherwise it won`t be able to save anything.