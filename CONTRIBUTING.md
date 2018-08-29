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

New APIs, libraries, should be treated as plugins and follow the same protocol as other plugins. If they only need to be used by a single script in a particular area, then they do not need to be included by the *main module*, and can be imported manually within the scrip, but should be imported using the same **require_once()** technique used in the main module.

# Misc

All information dealing with UI elements, Icons, etc. from the base template can be found [here](https://www.cubictheme.ga/cubic-html/).
