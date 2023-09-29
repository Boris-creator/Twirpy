import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import HomeView from '../views/HomeView.vue'

export const pathJoin = (...chunks: string[]) => chunks.join('/')
function prefix(prefixPath: string, routes: Array<RouteRecordRaw>) {
  return routes.map((route) => ({
    ...route,
    path: pathJoin(prefixPath, route.path)
  }))
}

export const ROUTE_NAMES = {
  home: 'home',
  books: 'books',
  book: 'book',
  addBook: 'addBook'
}
export const CHUNKS = {
  books: '/books',
  book: ':id',
  addBook: 'add'
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: ROUTE_NAMES.home,
      component: HomeView
    },
    ...prefix(CHUNKS.books, [
      {
        path: '',
        name: ROUTE_NAMES.books,
        component: () => import('@/components/Books/BookList.vue')
      },
      {
        path: CHUNKS.book,
        name: ROUTE_NAMES.book,
        component: () => import('@/components/Books/Page/BookPage.vue'),
        props: true
      },
      {
        path: CHUNKS.addBook,
        name: ROUTE_NAMES.addBook,
        component: () => import('@/components/Books/AddBookPage.vue')
      }
    ])
  ]
})

router.beforeEach((to, from, next) => {
  document.title = to.name as string
  next()
})

export default router
