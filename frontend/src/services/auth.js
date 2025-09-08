export function getStoredUser() {
  try {
    return JSON.parse(localStorage.getItem('user') || 'null')
  } catch {
    return null
  }
}

export function isAuthenticated() {
  return !!localStorage.getItem('access_token')
}

export function hasRoleAccess(userRoles = [], requiredRoles = []) {
  if (!requiredRoles.length) return true
  const set = new Set(userRoles.map((r) => String(r).toLowerCase()))
  return requiredRoles.some((r) => set.has(String(r).toLowerCase()))
}
