//
//  emotionInput.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-24.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "mlViewEmotionInput.h"
#import "toolIosSize.h"

@implementation mlViewEmotionInput

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        self.frame = CGRectMake(0, [toolIosSize heightWithoutNav]-30, 320, 30);
        self.backgroundColor = [UIColor lightGrayColor];
        
        UIScrollView *scroll = [[UIScrollView alloc] initWithFrame:CGRectMake(2, 2, 320, 26)];
        scroll.contentSize = CGSizeMake(4+30*(20+1)-1, 20);
        scroll.scrollEnabled = YES;
        for (int i = 0; i<30; i++) {
            UIButton *btn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
            btn.frame = CGRectMake(i*(20 +1)+2, 2, 20, 20);
            [btn addTarget:self.destination action:@selector(addEmotion:) forControlEvents:UIControlEventTouchUpInside];
            [scroll addSubview:btn];
        }
[self addSubview:scroll];
    }
    return self;
}

- (void)showInView:(UIView *)view
{
    [view addSubview:self];
    [UIView animateWithDuration:0.3 animations:^{
        self.transform = CGAffineTransformTranslate(self.transform, 0, -216);
    }];
}
- (void)hide
{
    [UIView animateWithDuration:0.3 animations:^{
        self.transform = CGAffineTransformTranslate(self.transform, 0, 216);
    } completion:^(BOOL comp){
        [self removeFromSuperview];
    }];
}

@end
