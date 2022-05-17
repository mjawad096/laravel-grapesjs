import toastr from 'toastr';

export default (editor, opts) => {
  editor.Commands.add('notify', (editor, sender, opts) => {
    toastr[opts.type](opts.message, opts.title)
  });
}