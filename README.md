# FFSB Admin

The website for the internal management of the FFSB. This is an improvement of the 2024 version that use more modern, secure and beautiful features.

Built on top of Laravel, Livewire, Flux (the free version) and TailwindCSS. The system incorporate also some self-made components (the Flux's ones behind a paywall mainly).

## Features

- Add documentaries
- Add evaluations
- Sort all the evaluations
- Report bugs
- Upload profile pictures
- Programs creation
- Production houses management
- Kanbans

## Deployment

The website is currently [available here](https://ffsb-admin.zandies.be). Each push on the `main` branch deploy the code on the server (hosted on a raspberry pi) through a Github Action.

## Report issue

If you find an issue in the code or in the application, you can use the Github's issues or the [dedicated page](https://ffsb-admin.zandies.be/support/bugs/report) in the website.

## Contribution

If you want to contribute, create a fork and then suggest PR.

## Les gouttes d'IA

Si vous me connaissez vous devez également connaître mon aversion envers la délégation de toute réflexion intellectuelle et artistique aux larges modèles de langage (et ne parlons même pas de l'aspect climatique et social). Bref. Je réchigne généralement à utiliser l'IA de quelque manière que ce soit, mais il arrive parfois que — après avoir planché sur un problèmes pendant plusieurs heures (voirs jours) — je lui expose mon problème. Pour une transparence totale de cet apport, j'inscris ci-dessous les différents endroits où elle a été utilisée (Proton ou Claude):

- Correction du bug dans l'algorithme de transformation des minutes en temps 'humain' (170 minutes donnait 2h70..)
- La découvert de l'attribut "Modelable" de Livewire que je ne connaissais pas et qui m'a bien aidé (même si maintenant il reste à refactorer quelques composants d'inputs)
- L'ajout du timing afin d'éviter un flickering lors du déplacement des tâches du kanban.
