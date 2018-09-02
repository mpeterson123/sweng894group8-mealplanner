# Coding Guidelines
These are simply recommendations, and are based on the base framework code.

**Functions and local variables** should follow camelCase
```
    function thisFunctionUsesCamelCase()
    {
      $aSimpleVariable = TRUE;
    }
```

**Global variables** (to the script) should have the first letter Uppercase
```
    $User = sqlRequest(...);
```
**Super globals** (definitions) should be contained in the **main module** (*/modules/main.mod.php*) and follow the same format
```
    define( '__EXAMPLE__' , 'pretty simple, eh?' );
```

> All variables and functions should be named/labelled what they are. Exception being instantiated index integers for loops.

> No same scope variable re-use for convenience. Create a new variable.

# Notes
Any universal changes to HTML code will be contained within the **HTML-based modules** in */modules/*
```
Examples:
    sidebar.mod.php    - Handles all of the code for the main navigation panel on the left part of the GUI
    footer.mod.php     - Contains all of the globally usable javascript code and javascript libs
```
# Plugins
A script requiring a particular plugin must activate that plugin by setting the global variable to true (so the module scripts will know to add the appropriate lines of CSS/HTML/Javascript code)

New APIs, libraries, should be treated as plugins and follow the same protocol as other plugins. If they only need to be used by a single script in a particular area, then they do not need to be included by the *main module*, and can be imported manually within the script, but should be imported using the same **require_once()** technique used in the main module.

# Version Control Guidelines
## Check code for trailing whitespace
Avoid committing files with trailing whitespace, by running `git diff --check` before committing.


## Commit Message Formatting
For commit messages, you can follow these guidelines, as seen in a [post by Chris Beams](https://chris.beams.io/posts/git-commit/):


1. Separate subject from body with a blank line
2. Limit the subject line to 50 characters
3. Capitalize the subject line
4. Do not end the subject line with a period
5. Use the imperative mood in the subject line
6. Wrap the body at 72 characters
7. Use the body to explain what and why vs. how

Example:
> Summarize changes in around 50 characters or less
>
> More detailed explanatory text, if necessary. Wrap it to about 72
characters or so. In some contexts, the first line is treated as the
subject of the commit and the rest of the text as the body. The
blank line separating the summary from the body is critical (unless
you omit the body entirely); various tools like `log`, `shortlog`
and `rebase` can get confused if you run the two together.
>
> Explain the problem that this commit is solving. Focus on why you
are making this change as opposed to how (the code explains that).
Are there side effects or other unintuitive consequences of this
change? Here's the place to explain them.
>
>Further paragraphs come after blank lines.
>
> - Bullet points are okay, too
> - Typically a hyphen or asterisk is used for the bullet, preceded
   by a single space, with blank lines in between, but conventions
   vary here
>
> If you use an issue tracker, put references to them at the bottom,
like this:
>
> Resolves: #123
> See also: #456, #789

# Misc

All information dealing with UI elements, Icons, etc. from the base template can be found [here](https://www.cubictheme.ga/cubic-html/).
