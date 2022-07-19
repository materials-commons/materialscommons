<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
    <link rel="icon" type="image/png" href="favicon-32x32.png">
    <title>ParaViewWeb Visualizer</title>
  </head>
  <body>
    <div class='content'></div>
    <script type="text/javascript" src="{{asset('js/Visualizer.js')}}"></script>
    <script>
      // Visualizer.connect({ application: 'visualizer' });
      Visualizer.connectWithArgsAsConfig({
        application: 'visualizer',
        sessionURL: 'ws://localhost/pv5/ws'
      });
      Visualizer.autoStopServer(10);
    </script>
  </body>
</html>
