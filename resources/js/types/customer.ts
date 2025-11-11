export interface CustomerType {
  id: string,
  name: string,
  tel: string,
  street: string,
  role: number ,
  ward?: number | undefined,
  canAccess: boolean | undefined,
}