jaams-web
=========

JAAMS CSC131 Project - Web portion

A very very simple web framework, mostly centered around form handling.

Includes:

- Core:
  - JAAMSTemplatable
    - Class Description
      A very general base class for dealing with templatable items.
    - Class Properties
      template_hierarchy
      -  Type: Array
      -  Default value: array(‘template’, ‘form’);
      -	Expected values: An array of strings that do not violate any system file naming conventions (i.e. no Unicode characters on systems that don’t support Unicode characters in filenames).
      -	Description: A non-associative array describing the hierarchy to use when searching for a template file (see JAAMSTemplatable class).
      template_dir_paths
      -	Type: Array
      -	Default value: An array containing one string describing the path to the class definition directory.
      -	Expected values: An array containing strings describing well-formed directory paths.
      -	Description: An ordered array of paths to the directories to use to attempt to find the form’s template file (see JAAMSTemplatable::get_template method).
      template_ext
      -	Type: String
      -	Default value: ‘php’
      -	Expected values: String containing a valid file extension.
      -	Description: Template file extension.
      template_sep
      -	Type: String
      -	Default value: ‘-’
      -	Expected values: String containing a valid file extension.
      -	Description: Template file extension.
  - JAAMSDebugger (Not ready for prime-time just yet).
- Forms
  - JAAMSForms_Base
    - Class Description
      "Abstract" base class for JAAMS Forms classes.
    - Class Inheritance
      Extends JAAMSTemplatable
    - Class Properties
      name*
        - Type: String
        -  Default value: None
        -	Expected values: Any string that can be used for an HTML tag attribute.
        -	Description: HTML form tag “name” attribute.  Also used internally as a general handle for the form.
      label
      -  Type: String
      -	Default value: Empty string
      -	Expected values: Any string.
      -	Description: A human readable name for the form.
      errors
      -  Type: Array
      -	Default value: Empty array
      -	Expected values: An array containing strings describing error messages associated with this fieldset.  This array should only contain the following indices (all of which are optional): { ‘groups’ | ‘inputs’ }, which should contain arrays corresponding the indices of the ‘groups’ and ‘inputs’ properties of this object.
      -	Description: An array of error messages to display in the error message portion of the group template.
  - JAAMSForms_Form
    - Class Description
      Defines and generates HTML forms using a template.  See the bottom of this document for usage example(s).
    - Class Inheritance
      Extends JAAMSForms_Base
    - Class Properties
      •  * indicates that the property must be set via the class constructor.
      •	+ indicates the property is actually a member of a parent class.
      fieldsets
      -	Type: Array
      -	Default values: Empty array
      -	Expected values: An array of fieldsets (see JAAMSFormFieldset class).
      -	Description: Fieldsets to be included in the form.
      groups
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array of groups (see JAAMSFormGroup class).
      -	Description: Groups not included in any fieldset to be included in the form.
      inputs
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array of inputs (see JAAMSFormInput class).
      -	Description: Inputs not included in any group or fieldset to be included in the form.
      atts
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array describing the attributes of the form.
      -	Description: Use this array to set HTML attributes of the form:
        -	method
          o	Type: String
          o	Default value: ‘post’
          o	Expected values: { ‘post’ | ‘get’ }
          o	Description: HTML form tag “method” attribute.
        -	action
          o	Type: String
          o	Default value: Empty string
          o	Expected values: A string describing a well-formed URL.
          o	Description: HTML form tag “action” attribute.
        -	enctype
          o	Type: String
          o	Default value: ‘text/plain’,
          o	Expected values: { ‘text/plain’ | ‘multipart/form-data’ | ‘application/x-www-form-urlencoded’ }
          o	Description: HTML form tag “enctype” attribute.
        -	{other}
          o	Type: String
          o	Description: Any valid HTML form tag attribute can be defined.
  - JAAMSForms_Fieldset
    - Class Description
      Defines and generates HTML for fieldsets using a template.
    - Class Inheritance
      Extends JAAMSForm_Base
    - Class Properties
      •  * indicates that a value for that property must be passed in the class constructor.
      •  + indicates the property is actually a member of a parent class.
      groups
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array of groups (see JAAMSFormGroup class).
      -	Description: Groups to be included in the fieldset.
      inputs
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array of inputs (see JAAMSFormInput class).
      -	Description: Inputs not included in any group to be included in the fieldset.
      label
      -	Type: String
      -	Default value: Empty string
      -	Expected values: Any string.
      -	Description: A human readable label for the fieldset.
      atts
      -  Type: Array
      -	Default value: Empty array
      -	Expected values: An array describing the attributes of the form.
      -	Description: Use this array to set HTML attributes of the form:
  - JAAMSForms_Group
    - Class Description
      Defines and generates HTML for groups of inputs using a template.
    - Class Inheritance
      Extends JAAMSForms_Base
    - Class Properties
      inputs
      -  Type: Array
      -	Default value: Empty array
      -	Expected values: An array of inputs (see JAAMSFormInput class).
      -	Description: Inputs not included in any group to be included in the group.
  - JAAMSForms_Input
    - Class Description
      Defines and generates HTML for groups of inputs using a template.
    - Class Inheritance
      Extends JAAMSForms_Base
    - Class Properties
      type
      -  Type: Integer
      -	Default value: JAAMSForms_InputTypes::::_TEXT
      -	Expected values: An integer (usually in the form of a provided constant, see Description) indicating the type of input to generate.
      -	Description: The JAAMSFormInput class definition file contains a series of constants:
        o	JAAMSForms_InputTypes::text
          •	HTML input tag with “type” attribute set to “text”.
        o	JAAMSForms_InputTypes::textarea
          •	HTML textarea tag.
        o	JAAMSForms_InputTypes::select
          •	HTML select tag.
        o	JAAMSForms_InputTypes::submit
          •	HTML input tag with “type” attribute set to “submit”.
        o	JAAMSForms_InputTypes::button
          •	HTML input tag with “type” attribute set to “button”.
        o	JAAMSForms_InputTypes::checkbox
          •	HTML input tag with “type” attribute set to “checkbox”.
        o	JAAMSForms_InputTypes::checkboxes
          •	A set of HTML input tags with “type” attribute set to “checkbox”.
        o	JAAMSForms_InputTypes::radios
          •	A set of HTML input tags with “type” attribute set to “radio”.
      atts
      -	Type: Array
      -	Default value: Empty array
      -	Expected values: An array describing the attributes of the input.
      -	Description: This array is used to set the HTML attributes of the input as key => value pairs (i.e. $atts[‘class’] => ‘class1 class2 class3’ becomes <input class=”class1 class2 class3” />).
      args
      -	Type: Array
      -	Default value: Empty array
      -	Description: an array with arguments pertinent to the input.
      default_value
      -	Type: Mixed
      -	Default value: None
      -	Expected values: A value of a type corresponding to the ‘type’ property of this object.
      -	Description: The default value for this input.
      current_value
      -	Type: Mixed
      -	Default value: None
      -	Expected values: A value of a type corresponding to the ‘type’ property of this object.
      -	Description: The current value for this input.


