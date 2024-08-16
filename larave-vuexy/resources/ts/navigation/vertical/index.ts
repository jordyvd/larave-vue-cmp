
export default [
  {
    title: 'Panel',
    to: { name: 'root' },
    icon: { icon: 'tabler-dashboard' },
  },
  {
    title: 'Comercial',
    to: { name: 'commercial' },
    icon: { icon: 'tabler-wallet' },
    children: [
      {
        title: 'Precios',
        to: { name: 'commercial-prices' },
        children: [
          {
            title: 'Fia',
            to: { name: 'commercial-prices-fia' },
          },
          {
            title: 'Infrima',
            to: { name: 'commercial-prices-infrima' },
          },
          {
            title: 'Atq',
            to: { name: 'commercial-prices-atq' },
          },
          {
            title: 'Quo',
            to: { name: 'commercial-prices-quo' },
          },
          {
            title: 'Ecuador',
            to: { name: 'commercial-prices-ecuador' },
          }
        ]
      },
    ],
  },
  // {
  //   title: 'Configuración',
  //   to: { name: 'setting' },
  //   icon: { icon: 'tabler-settings' },
  // },
  {
    title: 'Accesos',
    to: { name: 'access' },
    icon: { icon: 'tabler-lock-access' },
    children: [
      {
        title: 'Usuarios',
        to: { name: 'access-users' },
      },
      {
        title: 'Roles',
        to: { name: 'access-roles' },
      },
    ]
  },
]
