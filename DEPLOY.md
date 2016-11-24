## Deploying

**Warning** : to ease deployment while allowing hotfixes, the **local** changes are deployed on the server.
You need to make sure your local files are working before using the deploy command.

**Prerequisites** : to be able to mount the remote folder using SSHFS, you need to install [FUSE and SSHFS](https://osxfuse.github.io/), and [add your public SSH key in the Gandi admin area](http://wiki.gandi.net/fr/gandi/ssh-keys).

To mount the remote folder locally

```
$ bin/mount
```

This will create a folder `gallica.lol` on your machine.

To deploy the local changes to the server

```
$ bin/deploy
```

To unmount the remote folder

```
$ umount gallical.lol
```