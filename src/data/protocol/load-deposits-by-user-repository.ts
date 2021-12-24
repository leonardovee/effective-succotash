import { DepositModel } from '@/domain/model/deposit'

export interface LoadDepositsByUserRepository {
  loadByUser: (id: string) => Promise<DepositModel[]>
}
