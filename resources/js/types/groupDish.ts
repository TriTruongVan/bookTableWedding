export interface DishGroup {
  id: number;
  name: string;
}

export interface Dish {
  id: number;
  name: string;
  dish_group_id: number;
  description: string | null;
  dish_group?: DishGroup;
  group_name?: string;
}