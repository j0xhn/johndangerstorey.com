(function() {
  module.exports.register = function(Handlebars, options) {
    Handlebars.registerHelper("text_with_html", function(text, options) {
      return new Handlebars.SafeString(text);
    });
  };
}).call(this);
