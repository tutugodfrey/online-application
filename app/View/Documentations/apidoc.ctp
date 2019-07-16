<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Axia API Documentation</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/swagger-ui/swagger-ui.css" >
    <style>
      html
      {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }

      *,
      *:before,
      *:after
      {
        box-sizing: inherit;
      }

      body
      {
        margin:0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <span>
        <?php echo $this->Html->link($this->Html->image('/img/logo.png', array('style' => 'max-height:60px')), '/admin/', array('escape' => false)); ?>
      </span>
        <span class="navbar-text navbar-right btn-group" style="margin-right:20px">
          <?php
          echo $this->Html->link(__('Applications'),
            array(
              'controller' => 'cobrandedApplications',
              'action' => 'index',
              'admin' => true,
            ),
            array(
              'class' => 'btn btn-default'
            )
          );
        ?>
        </span>
    </nav>
    <div id="swagger-ui"></div>

    <script src="/js/swagger-ui/swagger-ui-bundle.js"> </script>
    <script src="/js/swagger-ui/swagger-ui-standalone-preset.js"> </script>
    <script>
    /// Disable Try it now button since we dont allow this and it is not possible
    const DisableTryItOutPlugin = function() {
      return {
        statePlugins: {
          spec: {
            wrapSelectors: {
              allowTryItOutFor: () => () => false
            }
          }
        }
      }
    }

    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "/js/swagger-ui/openapi_axia.json",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          // SwaggerUIBundle.plugins.DownloadUrl,
          DisableTryItOutPlugin
        ],
        layout: "StandaloneLayout"
      })
      // End Swagger UI call region

      window.ui = ui
    }
  </script>
  </body>
</html>
