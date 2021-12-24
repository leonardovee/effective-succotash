import { Deposit } from '@/domain/model/deposit'
import { Withdraw } from '@/domain/model/withdraw'

export interface CreateTransaction {
  create: (deposit: Deposit, withdraw: Withdraw) => Promise<TransactionModel>
}