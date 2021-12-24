import { WithdrawModel } from '@/domain/model/withdraw'

export interface LoadWithdrawsByUserRepository {
  loadByUser: (id: string) => Promise<WithdrawModel[]>
}
