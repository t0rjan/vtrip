//
//  mlViewEleTableLoadingInset.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-19.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewEleTableLoadingInset.h"

@implementation mlViewEleTableLoadingInset

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        self.frame = CGRectMake(0, -60 , 320, 60);
        self.backgroundColor = [UIColor clearColor];
        
        UILabel *lb = [[UILabel alloc] initWithFrame:CGRectMake(100, 3, 200, 50)];
        lb.text = @"玩命加载中...";
        lb.backgroundColor = [UIColor clearColor];
        lb.font = [UIFont systemFontOfSize:30];
        lb.textColor = [UIColor darkGrayColor];
        
        [self addSubview:lb];
    }
    return self;
}


@end
