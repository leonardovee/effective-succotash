import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'
import { AuthorizerRepository } from '../protocol/authorizer-repository'
import { CreateTransactionByDepositAndWithdrawRepository } from '../protocol/create-transaction-by-deposit-and-withdraw-repository'

export class DbCreateTransaction implements CreateTransaction {
  constructor (
    private readonly loadUserByIdRepository: LoadUserByIdRepository,
    private readonly loadWithdrawsByUserRepository: LoadWithdrawsByUserRepository,
    private readonly loadDepositsByUserRepository: LoadDepositsByUserRepository,
    private readonly authorizerRepository: AuthorizerRepository,
    private readonly createTransactionByDepositAndWithdrawRepository: CreateTransactionByDepositAndWithdrawRepository
  ) {}

  async create (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
    const payer = await this.loadUserByIdRepository.loadById(withdraw.user)
    if (payer.type === 'bussiness') {
      throw new UnauthorizedTransactionError()
    }
    
    const withdraws = await this.loadWithdrawsByUserRepository.loadByUser(payer.id)
    const withdrawsAmount = withdraws.map((v) => v.amount).reduce((v, a) => v + a, 0)

    const deposits = await this.loadDepositsByUserRepository.loadByUser(deposit.user)
    const depositsAmount = deposits.map((v) => v.amount).reduce((v, a) => v + a, 0)

    const balance = depositsAmount - withdrawsAmount

    if (withdraw.amount > balance) {
      throw new UnauthorizedTransactionError()
    }

    const isAuthorized = await this.authorizerRepository.authorize(deposit, withdraw)
    if (!isAuthorized) {
      throw new UnauthorizedTransactionError()
    }

    return await this.createTransactionByDepositAndWithdrawRepository.createByDepositAndWithdraw(deposit, withdraw)
  }
}
