export default (editor, opts = {}) => {
  editor.getConfig().showDevices = 0;
  editor.Panels.addPanel({
    id: 'devices-c', 
    buttons: [
      {
        id: 'set-device-desktop',
        className: 'fa fa-desktop',
        attributes: {
          title: 'Desktop',
        },
        command: e => e.setDevice('Desktop'),
      },
      {
        id: 'set-device-tablet',
        className: 'fa fa-tablet',
        attributes: {
          title: 'Tablet',
        },
        command: e => e.setDevice('Tablet'),
      },
      {
        id: 'set-device-mobile',
        className: 'fa fa-mobile',
        attributes: {
          title: 'Mobile',
        },
        command: e => e.setDevice('Mobile portrait'),
      },
    ]
  });
};