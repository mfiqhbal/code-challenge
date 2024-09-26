export default function auth ({ next }){
  if (!localStorage.token) {
    console.log(localStorage.token)
    return next({name: 'Login'})
  } 
  return next()
}