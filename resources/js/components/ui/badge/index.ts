import { cva, type VariantProps } from 'class-variance-authority'

export { default as Badge } from './Badge.vue'

export const badgeVariants = cva(
  'inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
  {
    variants: {
      variant: {
        default:
          'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80',
          secondary:
          'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
          destructive:
          'border-transparent bg-destructive text-destructive-foreground shadow hover:bg-destructive/80',
          outline: 'text-foreground',
          transaction_1:
            'border-transparent bg-red-500 text-primary-foreground shadow hover:bg-primary/80',
          transaction_2:
            'border-transparent bg-red-500 text-primary-foreground shadow hover:bg-primary/80',
          transaction_3:
            'border-transparent bg-green-500 text-primary-foreground shadow hover:bg-primary/80',
          transaction_4:
            'border-transparent bg-green-500 text-primary-foreground shadow hover:bg-primary/80',
          transaction_5:
            'border-transparent bg-primary text-primary-foreground shadow hover:bg-primary/80',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
)

export type BadgeVariants = VariantProps<typeof badgeVariants>
